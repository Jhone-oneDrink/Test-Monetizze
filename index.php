<?php
class FourAttributes
{
    
    private $_qtd_dez;      // Quantidade de dezenas, que dever´a permitir apenas as seguintes op¸c˜oes: 6, 7, 8, 9 ou 10.
    private $_result;       // Resultado
    private $_total_match;  // Total jogos
    private $_match;        // Jogos
    private $_qtd_raffle;   // Sorteio de 6 dezenas aleat´orias

    function __construct($qtd_dez, $total_match) 
    {
          $this->_qtd_dez = $qtd_dez; 
          $this->_total_match = $total_match; 
          $this->_qtd_raffle = 6;
    }
    

    public function getQtdDez()  
    {
        return $this->_qtd_dez;
    }

    public function getQtdMatch()  
    {
        return $this->_total_match;
    }

    public function setQtdDez($qtd_dez)  
    {
        if(!in_array($qtd_dez, array(6, 7, 8, 9, 9 ,10)))
            die("Informe uma quantidade de dezenas válidas entre 5 e 11");

        $this->_qtd_dez = $qtd_dez; 
    }

    public function setTotalMatch($total_match)  
    {
        $this->_total_match = $total_match; 
    }


    // Gerar números aleatórios de acordo com a quantidade definida
    private function _returnArrayDez($qtd = 0)
    {
        $qtd = ($qtd == 0 ? $this->_qtd_dez : $qtd);

        $result = array();

        for($i = 0; $i < $qtd; $i++)
        {
            $num = mt_rand(10, 60); //gerar dezenas entre 1 e 60
            
            /* DEFINIR COMO DEZENAS
            *  Transforma o número gerado em uma dezena
            *  a remoção dessa linha permite números entre as dezenas
            */
            //$num = (int)($num / 10) * 10; //definir num gerado como uma dezena 
  
            if(in_array($num, $result))
            {
                $i--;
                continue;

            }

            array_push($result, $num);

        }

        // retorna array ordenada
        sort($result);

        return $result;

    }

    // 1 - Gera os jogos apostados
    public function getTotalMatch()
    {
        $result = array();

        for($i = 0; $i<$this->_total_match; $i++)
            $result[] = $this->_returnArrayDez();

        $this->_match = $result;

    }

    // 2 - Sorteio os números do jogo
    public function raffleMatch()
    {
        $this->_result = $this->_returnArrayDez($this->_qtd_raffle);
    }
    
    public function checkResultMatch()
    {
        //pegar cartela
        $myNumbers = $this->_match;

        //pegar o jogo sorteado
        $raffleNumbers =  $this->_result;

        $check = array();

        foreach($myNumbers as $myNumber)
        {

            $qtsCorrect = 0;

             //Verifica paridade entre array
             $diff = array_intersect($raffleNumbers, $myNumber);

             $qtsCorrect = count($diff);
            
             array_push($check, $qtsCorrect);
        }

        return $check;

    }

    //Imprime resultado na tela
    public function printResult($resultChecked)
    {

        echo "
            
            <body>
                <div style='max-width: 1280px; width: 80%; margin: 0 auto; margin-top: 150px;'>
                    <h1>Atualize a tela para gerar novos números</h1>
                    <div style='text-align: left;'>
        ";

        /*########## Tabela com os números sorteados ############### */
        $tableRaffle = "<table border='1'>";
        $line = "";

        $line .= "<tr>"; 

        for($j = 0; $j < $this->_qtd_raffle; $j++)
        {
            $line .= "<td style='width: 40px; text-align: center;'>" . $this->_result[$j] . "</td>";
        }

        $line .= "</tr>";
        // }

        $tableRaffle .= $line . "</table>";
        
        /* ########################################################### */


        /*########## Tabela de jogos com resultados do sorteio  ###############*/
        $tableResult = "<table border='1'>";
        $line = "";

        for($i = 0; $i < $this->_total_match; $i++)
        {
            $line .= "<tr>";

            for($j = 0; $j < $this->_qtd_dez; $j++)
            {
                $line .= "<td style='width: 40px; text-align: center;'>" . $this->_match[$i][$j] . "</td>";
            }

            $line .= "<td style='width: 100px; text-align: center;'> => </td>";
            $line .= "<td style='width: 80px; text-align: center;'>Acertos: " . $resultChecked[$i] . "</td>";

            $line .= "</tr>";
        }

        $tableResult .= $line . "</table>";

        /* ########################################################### */

        echo "<p style='margin-top: 30px;margin-bottom: 0px'><strong>Dezenas Sorteadas: </strong></p><br/>";
        echo $tableRaffle;

        echo "<p style='margin-top: 30px;margin-bottom: 0px'><strong>Resultado dos jogos: </strong></p><br/>";
        echo $tableResult;

        echo "</div></div></body>";

    }


}


    $game = new FourAttributes(10,3);

    $game->setQtdDez(10); //Entre 5 e 11
    $game->setTotalMatch(3); 

    //Gera os jogos
    $game->getTotalMatch();
    
    //sortear número dos jogos
    $game->raffleMatch();

    $resultChecked = $game->checkResultMatch();

    $game->printResult($resultChecked);

?>