<?php

namespace App\Database\Seeds;

class CadastroMetodoPagamentoSeeder extends DatabaseSeeder
{
    /**
     * Executa o seeder.
     * @return void
     */
    public function run()
    {
        $this->saveOnce('cadastro_metodo_pagamento', ['nome' => 'Dinheiro']);
        $this->saveOnce('cadastro_metodo_pagamento', ['nome' => 'Cheque']);
        $this->saveOnce('cadastro_metodo_pagamento', ['nome' => 'Cartão de Crédito']);
        $this->saveOnce('cadastro_metodo_pagamento', ['nome' => 'Cartão de Débito']);
        $this->saveOnce('cadastro_metodo_pagamento', ['nome' => 'Crédito de Loja']);
        $this->saveOnce('cadastro_metodo_pagamento', ['nome' => 'Vale Presente']);
        $this->saveOnce('cadastro_metodo_pagamento', ['nome' => 'Boleto Bancário']);
        $this->saveOnce('cadastro_metodo_pagamento', ['nome' => 'Depósito Bancário']);
        $this->saveOnce('cadastro_metodo_pagamento', ['nome' => 'Pagamento Instantâneo (PIX)']);
        $this->saveOnce('cadastro_metodo_pagamento', ['nome' => 'Transferência bancária, Carteira Digital']);
        $this->saveOnce('cadastro_metodo_pagamento', ['nome' => 'Programa de fidelidade, Cashback, Crédito Virtual']);
        $this->saveOnce('cadastro_metodo_pagamento', ['nome' => 'Sem Pagamento']);
        $this->saveOnce('cadastro_metodo_pagamento', ['nome' => 'Outros']);
        // $this->saveOnce('cadastro_metodo_pagamento', ['nome' => 'Vale Alimentação']);
        // $this->saveOnce('cadastro_metodo_pagamento', ['nome' => 'Vale Refeição']);
        // $this->saveOnce('cadastro_metodo_pagamento', ['nome' => 'Vale Combustível']);
    }
}
