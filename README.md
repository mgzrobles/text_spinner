# Text Spinner

## Introduction

Modules such as Text Spinner want to provide a way to generate different, fresh and as human writed versions of a text.
This module provides an API to do that and also provide a Drupal filter to use in text fields.
Text Spinner is a usefull module for SEO and autogenerate content for google.

## API use example

```php
use Drupal\text_spinner\Spinner\TextSpinner;
...
$text = 'text_spinner is a {{{beautiful |}{{content |}text |}{spinner|generator}}|{{wonderful |}{service|tool}}} which {allows you to|can} {easily|recursively} {spin|rotate|generate} {text|text content|content}.

Give it a try ;)';
$spin = TextSpinner::spin($text);
/*
* Results:
* text_spinner is a wonderful service which can easily generate text content. Give it a try ;)
* text_spinner is a service which allows you to easily rotate text content. Give it a try ;)
* text_spinner is a tool which can easily generate text. Give it a try ;)
* text_spinner is a wonderful tool which can easily generate text content. Give it a try ;)
*/
```

## How to form the text to spin it  

- You have to put the content to spin between curly brackets {}: 
  -  "this is a {content} to spin"
- Next step is separate options with pipes"|": 
  - "this is a {content|text} to spin"
- You can write more than two options and also use braces into braces:
  - "this is a {content|text|{great {article|post}}} to spin"
- If you want an empty option, you only need to add it:
  - "this is a {great |}{content|text|article|post} to spin"
- Of course you have possibility to escape reserved characters using a backslash before the character you want escape:
  - "this are curly brackets \\{\\} that are {reserve|special|symbols} used by the spinner"
  - Note that is a unique backslash, but in the example I use two to do visible it.

## Can I use HTML into the text to spin?
Yes, you can. The only symbols we use internally are {}| and \ to escape the previous ones.

## Similar services
- http://sp1n.me/index.html
- http://freespinner.net/