<?php
namespace Storemaker\App\Models\Ajax;
use Illuminate\Database\Eloquent\Model;

class PersistentData extends Model {
	protected $table = 'persistent_data',
				 $fillable = ['userID', 'itemID', 'languageID', 'section', 'name', 'current', 'data'];
}