<?php

namespace Form\Tests\Unit;

test('environment is set to testing', function () {
    expect(config('app.env'))->toBe('testing');
});
