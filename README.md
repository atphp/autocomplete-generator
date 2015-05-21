# Autocomplete generator [![Build Status](https://travis-ci.org/atphp/autocomplete-generator.svg?branch=v0.1)](https://travis-ci.org/atphp/autocomplete-generator)

This a a PHP CLI application to generate PHP code for classes from PHP extensions.

### Usage

```bash
# Install
composer global require atphp/autocomplete-generator:~0.1.0

# Add this line to ~/.bash_profile
export PATH="$HOME/.composer/vendor/bin:$PATH"

# Generate PHP code for the classes provivded by gmagick extension.
echo '<?php' > idehelper.php
autocomplete-generator Gmagick GmagickDraw GmagickPixel >> idehelper.php
```
