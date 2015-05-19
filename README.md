# Autocomplete generator [![Build Status](https://travis-ci.org/atphp/autocomplete-generator.svg)](https://travis-ci.org/atphp/autocomplete-generator)

This a a PHP CLI application to generate PHP code for classes from PHP extensions.

### Usage:

```bash
# Install
composer global require atphp/autocomplete-generator

# Add this line to ~/.bash_profile
export PATH="$HOME/.composer/vendor/bin:$PATH"

# Generate PHP code for the classes provivded by gmagick extension.
echo '<?php' > idehelper.php
autocomplete-generator Gmagick GmagickDraw GmagickPixel >> idehelper.php
```
