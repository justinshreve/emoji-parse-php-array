# emoji-parse-php-array
Creates a PHP array of emoji from the unicode.org site

Fork of https://github.com/iamcal/php-emoji (basically just ripped out the relevant parts and changed some of the output comments/names)

Only returns names and HTML output

Original code by Cal Henderson cal@iamcal.com

```
wget http://www.unicode.org/~scherer/emoji4unicode/snapshot/full.html
php parse.php full.html > catalog.php
php build_map.php > emoji-maps.php
```

emoji-maps.php contains an array of emoji
