# Fast, extensible, server-side code highlighting

## Quickstart

Highlight code like this:

```php
$highlighter = new \Tempest\Highlight\Highlighter();

$code = $highlighter->parse($escapedCode, 'php');
```

**Note: you should always pass the _escaped_ version of your code**:

```php
$code = $highlighter->parse(htmlentities($raw), 'php');
```

Next, you can import one of the provided themes:

```css
@import "../vendor/tempest/highlight/src/Themes/highlight-light-lite.css";
```

Or you can build your own with just a couple of classes:

```css
.hl-keyword {
    color: #4F95D1;
}

.hl-property {
    color: #46b98d;
}

.hl-attribute {
    font-style: italic;
}

.hl-type {
    color: #D14F57;
}

.hl-generic {
    color: #9D3AF6;
}

.hl-comment {
    color: #888888;
}
```

You should style `<pre>` tags yourself.

## CommonMark integration

If you're using `league/commonmark`, you can add highlight support to codeblocks like so:

```php
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\MarkdownConverter;
use Tempest\Highlight\CommonMark\HighlightCodeBlockRenderer;

$environment = new Environment();

$environment
    ->addExtension(new CommonMarkCoreExtension())
    ->addRenderer(FencedCode::class, new HighlightCodeBlockRenderer());

$markdown = new MarkdownConverter($environment);
```

Keep in mind that you need to manually install `league/commonmark`:

```php
composer require league/commonmark;
```

## Language support

This package makes it easy for developers to add new languages or extend existing languages. Right now, these languages are supported: `php`, `html`, `css`. More will be added.

In order to build your own highlighter functionality, you need to understand two concepts of how code is highlighted.

**1. Patterns**

A _pattern_ represents part of your code that should be highlighted. A _pattern_ can target a single keyword like `return` or `class`, or it could be any part of your code, like for example a comment: `/* this is a comment */` or an attribute: `#[Get(uri: '/')]`.

Each _pattern_ is represented by a simple class that provides a regex pattern, and a `TokenType`. The regex pattern is used to match relevant content to this specific _pattern_, while the `TokenType` is an enum value that will determine how that specific _pattern_ is colored.

Here's an example of a simple _pattern_ to match the namespace of a PHP file:

```php
use Tempest\Highlight\Pattern;
use Tempest\Highlight\Patterns\IsPattern;
use Tempest\Highlight\Tokens\TokenType;

final readonly class NamespacePattern implements Pattern
{
    use IsPattern;

    public function getPattern(): string
    {
        return 'namespace (?<match>[\w\\\\]+)';
    }

    public function getTokenType(): TokenType
    {
        return TokenType::TYPE;
    }
}
```

Note that each pattern must include a regex capture group that's named `match`. The content that matched within this group will be highlighted.

For example, this regex `namespace (?<match>[\w\\\\]+)` says that every line starting with `namespace` should be taken into account, but only the part within the named group `(?<match>…)` will actually be colored. In practice that means that the namespace name matching `[\w\\\\]+`, will be colored.

Yes, you'll need some basic knowledge of regex. Head over to [https://regexr.com/](https://regexr.com/) if you need help, or take a look at the existing patterns in this repository.

In summary:

- Patterns provide a regex that matches parts of your code
- Those regexes should contain a group named `match`, which is written like so `(?<match>…)`
- Finally, a pattern provides a `TokenType`, which is used to determine the highlight style for the specific match

**2. Injections**

### Extending existing languages

Instead of starting from scratch, the best approach to adding new languages is by extending existing ones. For example, let's add support for `blade`:

```php
class BladeLanguage extends HtmlLanguage
{
}
```

### Adding your own languages
