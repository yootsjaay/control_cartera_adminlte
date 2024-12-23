<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TipoSeguro
 * 
 * @property int $id
 * @property string $nombre
 * @property bool $activo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Poliza[] $polizas
 * @property Collection|SubTipoSeguro[] $sub_tipo_seguros
 *
 * @package App\Models
 */
class TipoSeguro extends Model
{
	protected $table = 'tipo_seguros';

	protected $casts = [
		'activo' => 'bool'
	];

	protected $fillable = [
		'nombre',
		'activo'
	];

	public function polizas()
	{
		return $this->hasMany(Poliza::class);
	}

	public function sub_tipo_seguros()
	{
		return $this->hasMany(SubTipoSeguro::class);
	}
}
