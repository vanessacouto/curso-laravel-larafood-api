<?php

namespace App\Tenant\Rules;

use App\Tenant\ManagerTenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Validation\Rule;

class UniqueTenant implements Rule
{
    // qual tabela que quer garantir o valor unico
    protected $table;
    // necessita desses valores para poder editar o registro, 
    // senão sempre cai na validacao de que o valor já está em uso
    protected $value, $column;
    
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $table, $value = null, $column = 'id')
    {
        $this->table = $table;
        $this->value = $value;
        $this->column = $column;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $tenantId = app(ManagerTenant::class)->getTenantIdentify();
        
        // verifica se já existe o valor na tabela que foi passada pelo construtor
        // esse valor deve ser unico por tenant
        $register = DB::table($this->table)
                ->where($attribute, $value)
                ->where('tenant_id', $tenantId)
                ->first();

        // verifica se o valor da coluna que está tentando editar é igual ao valor recebido como exceção
        if ($register && $register->{$this->column} == $this->value) {
            return true;
        }

        return is_null($register);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'O valor para :attribute já está em uso!';
    }
}
