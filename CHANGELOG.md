CHANGELOG
=========

Current master branch
---------------------

* [#10] correct SimpleHashKeyGenerator to ensure key unicity for big arrays. **BC break : this change will affect how `SimpleHashKeyGenerator` generates cache keys. Consequently, if you use this generator, your cache will be invalidated.**
