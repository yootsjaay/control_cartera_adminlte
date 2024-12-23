<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SubTipoSeguro
 * 
 * @property int $id
 * @property int $tipo_seguro_id
 * @property string $nombre
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property TipoSeguro $tipo_seguro
 *
 * @package App\Models
 */
class SubTipoSeguro extends Model
{
	protected $table = 'sub_tipo_seguros';

	protected $casts = [
		'tipo_seguro_id' => 'int'
	];

	protected $fillable = [
		'tipo_seguro_id',
		'nombre'
	];

	public function tipo_seguro()
	{
		return $this->belongsTo(TipoSeguro::class);
	}
}
