<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use \Spatie\Permission\Models\Role as BaseRole;

class Role extends BaseRole
{
    use HasFactory;

    const ROLE_SUPERADMIN = 'superadmin';
    const ROLE_OPERATOR = 'admin_operator'; // golinucci operators
    /**
     * TODO 
     * ​I “sottoprodotti” delle polizze collettive o di quelle individuali generate 
     * dalla piattaforma devono riguardare area con accesso riservato a nostri colleghi, 
     * come PRISMABROKER di Lucca (ma non solo) , che utilizza il prodotto principale, 
     * ma all’interno dell’area sottoprodotto si puo’ modificare la dichiarazione, i 
     * documenti allegati, inserire un logo nella webpage diverso da quello di GOLINUCCI 
     * o aggiuntivo a quello di GOLINUCCI , anche le modalità di pagamento si cambiano 
     * ( iban e carta credito di PRISMABROKER, EC…) . In questo modo GOLINUCCI recupera 
     * il file bordereaux complessivo, ma non riceve pagamenti diretti dalle singole
     * transazioni dei clienti di PRISMABROKER.
     * Il cliente di PRISMABROKER riceve l’email di conferma copertura, con allegati i 
     * documenti “logo Prismabroker”; sarà un estratto conto fra GOLINUCCI e PRISMABROKER 
     * a regolare il pagamento degli utenti che hanno utilizzato la piattaforma GOLINUCCI, 
     * nel “sottoprodotto”. Nell’area riservata PRISMABROKER puo’ “leggere” il suo 
     * portafoglio di clienti e/o apportare modifiche su anagrafiche, invio email avviso, 
     */
    const ROLE_AGENT = 'admin_agent'; // external operators
    const ROLE_USER = 'client_user'; // client users

    public static function defaultRoles()
    {
        return [
            self::ROLE_SUPERADMIN,
            self::ROLE_OPERATOR,
            self::ROLE_AGENT,
            self::ROLE_USER
        ];
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            'role_has_permissions',
            'role_id',
            'permission_id'
        );
    }
}
