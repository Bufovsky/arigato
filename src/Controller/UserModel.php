<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class UserModel
{
    public $login = '';
    private $password = '';
    private $email = '';
    private $avalableParams = ['login', 'password', 'email'];

    /**
     * Funkcja tworząca nowego użytkownika ustawiająca wartości do objektu
     * @param login Account login
     * @param password Account password
     * @param email Account email
     * @return Response
     */
    public function index(string $login = 'admin', string $password = 'password', string $email = 'admin@admin.pl'): Response {

        echo $this->get('kernel')->getProjectDir();

        //przypisanie wartości parametrów
        $params = func_get_args();

        $this->setParametersAssertTest($params);

        //validacja adresu email -> Możliwość refraktoryzacji do specjalistycznej klasy użytej w zadaniu 3.
        $this->emailValidation($email);

        //hashowanie hasła
        $this->password = sha1($password);

        //wyświetlanie wartości parametrów
        $this->getParametersAssertTest($params);

        //raport
        return new Response("[RESULT] New user parameters stored successfully!");
    }

    /**
     * Widok ustawiania parametrów
     * @param email Wstaw email
     */
    public function emailValidation(string $email) {
        $this->email = filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : 'uncorrect';
        $value = $this->email != 'uncorrect' ? 'correct' : 'uncorrect';
        echo $this->setMessage('CHECK EMAIL', sprintf("%s</br>", $value));
    }

    /**
     * Widok ustawiania parametrów
     * @param params Wartości parametrów
     */
    public function setParametersAssertTest(array $params) {
        $i = 0;
        foreach ($this->avalableParams as &$param) {
            $value = is_string($params[$i]) ? $params[$i++] : 'Wprowadź prawidłowy typ danych.';
            $this->__set($param, $value);
            echo $this->setMessage('SET PARAMETER', sprintf("%s = %s</br>", $param, $value));
        }
    }

    /**
     * @param parameter Set parameter
     * @param value Parameter value
     */
    public function __set($parameter, $value): void {
        if (isset($this->{$parameter})) {
            $this->{$parameter} = $value;
        }
    }


    /**
     * Widok Wyświetlania parametrów
     * @param params Wartości inputu
     */
    public function getParametersAssertTest(array $params) {
        foreach ($this->avalableParams as &$param)
            echo $this->setMessage('GET PARAMETER', sprintf("%s == %s</br>", $param, $this->__get($param)));
    }

    /**
    * @param parameter Set or create parameter
    * @return Parameter value
    */
    public function __get($parameter) {
        if (isset($this->{$parameter})) {
            return $this->{$parameter};
        }
    }

    /**
     * Function for destruct object.
     * @param header String to header section
     * @param body String to body section
     */
    private function setMessage(string $header, string $body): string {
        return sprintf("[%s] %s", $header, $body);
    }

    /**
     * Function for destruct object.
     */
    function __destruct() {
        echo "</br>[STATUS] CRUD Complete Correctly.</br>[DELETE OBJECT]";
    }
}