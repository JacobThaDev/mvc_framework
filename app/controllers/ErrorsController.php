<?php
class ErrorsController extends Controller {

    public function show401() {
        return true;
    }

    public function show404() {
        return true;
    }

    public function show500() {
        return true;
    }

    public function missing() {
        return true;
    }
}