<?php

it('redirects guests from the startup page to login', function () {
    $response = $this->get('/');

    $response->assertRedirect(route('login'));
});
