<?php

/*
Autor: Anderson Gonçalves (Bonus)
Data de criação: 11/01/2015

Este código foi criado de um desenvolvedor para desenvolvedores.
Use, modifique ou redistribua.
*/

class Data {
    
    //Constantes do dia da semana, para facilitar a escrita e leitura do codigo
    const DOMINGO = 0;
    const SEGUNDA = 1;
    const TERCA = 2;
    const QUARTA = 3;
    const QUINTA = 4;
    const SEXTA = 5;
    const SABADO = 6;

    //A classe inteira trabalhará em cima deste propriedade
    private $data;
    
    //Região para calcular a data
    private $regiao = "America/Sao_paulo";
    
    //Meses por extenso
    private $meses_extenso = array(
        1 => "Janeiro",
        2 => "Fevereiro",
        3 => "Março",
        4 => "Abril",
        5 => "Maio",
        6 => "Junho",
        7 => "Julho",
        8 => "Agosto",
        9 => "Setembro",
        10 => "Outubro",
        11 => "Novembro",
        12 => "Dezembro"
    );
    
    private $dia_semana_extenso = array(
        0 => "Domingo",
        1 => "Segunda",
        2 => "Terça",
        3 => "Quarta",
        4 => "Quinta",
        5 => "Sexta",
        6 => "Sábado"
    );


    //Dias uteis de segunda a sexta
    private $dias_uteis = array(1, 2, 3, 4, 5);

    //Construtor
    //Seta a região e valida a data de entrada
    //A data é opcional
    //São aceitos os formatos de dara '9999-99-99' ou 99/99/9999
    //Caso não seja os um destes formatos, o dia atual será carregado
    public function __construct($data = "") {
        $this->set_regiao($this->regiao);
        $this->validar($data);
    }
    
    //Adiciona X dias a data
    public function add_dias($dias) {
        $dias = (int)$dias;
        $this->data = date("Y-m-d", strtotime($this->data . " +" . $dias . " day"));
    }
    
    //Remove X dias a data
    public function remove_dias($dias) {
        $dias = (int)$dias;
        $this->data = date("Y-m-d", strtotime($this->data . " -" . $dias . " day"));
    }
    
    //Adiciona X meses a data
    public function add_mes($mes) {
        $mes = (int)$mes;
        $this->data = date("Y-m-d", strtotime($this->data . " +" . $mes . " month"));
    }
    
    //Remove X meses a data
    public function remove_mes($mes) {
        $mes = (int)$mes;
        $this->data = date("Y-m-d", strtotime($this->data . " -" . $mes . " month"));
    }
    
    //Adiciona X anos a data
    public function add_ano($ano) {
        $ano = (int)$ano;
        $this->data = date("Y-m-d", strtotime($this->data . " +" . $ano . " year"));
    }
    
    //Remove X anos a data
    public function remove_ano($ano) {
        $ano = (int)$ano;
        $this->data = date("Y-m-d", strtotime($this->data . " -" . $ano . " year"));
    }
    
    //Valida a data atual
    //Aceita os formatos '9999-99-99' ou '99/99/9999'
    //Caso não seja um destes formatos, a data atual será carregada
    private function validar($data) {
        $data_aux = explode("/", $data);
        if (count($data_aux) !== 3) {
            $data_aux = explode("-", $data);
            if (count($data_aux) !== 3) {
                $data = date("Y-m-d");
            }
        } else {
            $data = implode("-", array_reverse($data_aux));
        }
        
        $this->data = $data;
    }
    
    //Retorna true se a data da classe for dia útel
    public function dia_util() {
        $dia_semana = date("w", strtotime($this->data));
        return in_array($dia_semana, $this->dias_uteis);
    }
    
    //Calcula o próximo dia útil
    //Se for passado os dias da semana no array,
    //Será retornado o próximo dia dentre os dias da semana passado como parâmetro
    public function proximo_dia_util($dia = 1, $dias = array()) {
        for (;$dia > 0; $dia--) {
            $this->add_dias(1);
            while (!$this->dia_util() || !(count($dias) && in_array($this->dia_semana(), $dias))) {
                $this->add_dias(1);
            }
        }
    }
    
    //Retorna o dia da semana da data
    //Se for passado true como parâmetro, retorna o dia da semana por extenso
    public function dia_semana($extenso = false) {
        $dia_semana = (int)date("w", strtotime($this->data));
        if ($extenso) {
            return $this->dia_semana_extenso[$dia_semana];
        }
        return $dia_semana;
    }
    
    //Retorna a quantidade de dias de diferença entre a data atual e a data passada por parâmetro
    //Aceita como parâmetro um objeto Data ou o formato '9999/99/99' ou 99/99/9999
    public function diferenca($data) {
        $data_aux = new Data($this->data);
        if (!($data instanceof Data)) {
            $data = new Data($data);
        }
        if ($data_aux->get_data() < $data->get_data()) {
            $sinal = "add_dias";
        } else {
            $sinal = "remove_dias";
        }
        
        $dias = 0;
        
        while ($data_aux->get_data() != $data->get_data()) {
            $data_aux->{$sinal}(1);
            $dias++;
        }
        
        return $dias;
    }
    
    //Retorna a data no formato para usar com banco de dados
    public function get_data() {
        return date("Y-m-d", strtotime($this->data));
    }
    
    //Retorna a data no formato brasileiro 99/99/9999
    public function get_data_br() {
        return date("d/m/Y", strtotime($this->data));
    }
    
    //Mudar a região, o padrão é 'America/Sao_paulo'
    public function set_regiao($reagiao) {
        date_default_timezone_set($reagiao);
    }
    
    //Retorna a data por extenso
    public function get_extenso() {
        $dia = $this->get_dia();
        $mes = $this->meses_extenso[$this->get_mes()];
        $ano = $this->get_ano();
        return $dia . " de " . $mes . " de " . $ano;
    }
    
    //Retorna o dia
    public function get_dia() {
        return (int)date("d", strtotime($this->data));
    }
    
    //Retorna o mes
    public function get_mes() {
        return (int)date("m", strtotime($this->data));
    }
    
    //Retorna o ano
    public function get_ano() {
        return (int)date("Y", strtotime($this->data));
    }
    
}
