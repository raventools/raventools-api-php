clean:
	rm -rf docs
	rm -rf build

test:
	phpunit

docs:
	phpdoc