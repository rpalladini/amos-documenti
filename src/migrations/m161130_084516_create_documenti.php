<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 * @see http://example.com Developers'community
 * @license GPLv3
 * @license https://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3
 *
 * @package    lispa\amos\documenti
 * @category   CategoryName
 * @author     Lombardia Informatica S.p.A.
 */

use yii\db\Migration;
use lispa\amos\documenti\models\Documenti;

class m161130_084516_create_documenti extends Migration
{
    private $tabella = null;

    public function __construct()
    {
        $this->tabella = Documenti::tableName();
        parent::__construct();
    }

    public function safeUp()
    {
        if ($this->db->schema->getTableSchema($this->tabella, true) === null) {
            $this->createTable($this->tabella, [
                'id' => $this->primaryKey(11),
                'titolo' => $this->string(255)->defaultValue(null)->comment('Titolo'),
                'sottotitolo' => $this->string(255)->defaultValue(null)->comment('Sottotitolo'),
                'descrizione_breve' => $this->string(255)->defaultValue(null)->comment('Descrizione breve'),
                'descrizione' => $this->text()->comment('Descrizione'),
                'metakey' => $this->text()->comment('Meta key'),
                'metadesc' => $this->text()->comment('Meta descrizione'),
                'primo_piano' => $this->smallInteger(1)->defaultValue(0)->comment('Primo piano'),
                'filemanager_mediafile_id' => $this->integer(11)->defaultValue(null)->comment('Documento principale'),
                'hits' => $this->integer(11)->defaultValue(null)->comment('Visualizzazioni'),
                'abilita_pubblicazione' => $this->smallInteger(1)->defaultValue(0)->comment('Abilita pubblicazione'),
                'in_evidenza' => $this->smallInteger(1)->defaultValue(0)->comment('In evidenza'),
                'data_pubblicazione' => $this->date()->defaultValue(null)->comment('Data pubblicazione'),
                'data_rimozione' => $this->date()->defaultValue(null)->comment('Data fine pubblicazione'),
                'documenti_categorie_id' => $this->integer(11)->notNull()->comment('Categoria'),
                'status' => $this->string(255)->defaultValue(null)->comment('Stato'),
                'created_at' => $this->dateTime()->defaultValue(null)->comment('Creato il'),
                'updated_at' => $this->dateTime()->defaultValue(null)->comment('Aggiornato il'),
                'deleted_at' => $this->dateTime()->defaultValue(null)->comment('Cancellato il'),
                'created_by' => $this->integer(11)->defaultValue(null)->comment('Creato da'),
                'updated_by' => $this->integer(11)->defaultValue(null)->comment('Aggiornato da'),
                'deleted_by' => $this->integer(11)->defaultValue(null)->comment('Cancellato da')
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
            $this->addForeignKey('fk_documenti_documenti_categorie1', $this->tabella, 'documenti_categorie_id', 'documenti_categorie', 'id');
            $this->addForeignKey('fk_documenti_filemanager_mediafile1_idx', $this->tabella, 'filemanager_mediafile_id', 'filemanager_mediafile', 'id');
        } else {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
        }

        return true;
    }

    public function safeDown()
    {
        if ($this->db->schema->getTableSchema($this->tabella, true) !== null) {
            $this->execute("SET FOREIGN_KEY_CHECKS = 0;");
            $this->dropTable($this->tabella);
            $this->execute("SET FOREIGN_KEY_CHECKS = 1;");
        } else {
            echo "Nessuna cancellazione eseguita in quanto la tabella non esiste";
        }

        return true;
    }
}
