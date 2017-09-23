# Autocomplete generator [![Build Status](https://travis-ci.org/atphp/autocomplete-generator.svg?branch=v0.1)](https://travis-ci.org/atphp/autocomplete-generator)

This a a PHP CLI application to generate PHP code for classes from PHP extensions.

### Usage

```bash
# Install
composer global require atphp/autocomplete-generator:~0.2.0 --ignore-platform-reqs

# Add this line to ~/.bash_profile
export PATH="$HOME/.composer/vendor/bin:$PATH"

# Example usage.
autocomplete-generator trader grpc protobuf

# OUTPUT
# > trader's code is exported to `/var/folders/8w/2qp85cb50470bfd8_70jp8300000gn/php/trader`.
# > grpc's code is exported to `/var/folders/8w/2qp85cb50470bfd8_70jp8300000gn/php/grpc`.
# > protobuf's code is exported to `/var/folders/8w/2qp85cb50470bfd8_70jp8300000gn/php/protobuf`.
```
