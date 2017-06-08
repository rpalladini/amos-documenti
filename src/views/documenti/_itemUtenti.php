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

use lispa\amos\documenti\AmosDocumenti;

?>

<div class="listview-container">
    <div class="bk-listViewElement">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <h2><?= $model->titolo ?></h2>
                <h3><?= $model->sottotitolo ?></h3>
                <p><br><u><?= AmosDocumenti::t('amosdocumenti', 'Abstract') ?></u>: <i><?= $model->descrizione_breve ?></i></p>
                <p><?= $model->descrizione ?></p>
                <p><b><?= AmosDocumenti::t('amosdocumenti', 'Categoria') ?>:</b> <?= $model->documentiCategorie->titolo ?></p>
                <div class="bk-elementActions">
                    <?= $buttons ?>
                </div>
                <div class="clear"></div>
            </div>
            <div class="col-lg-12 col-md-12">
                <br>
                <h3><b><?= AmosDocumenti::t('amosdocumenti', 'Allegati') ?></b></h3>
                <?php
                $allegati = $model->getDocumentiAllegatis();
                if ($allegati->count() == 0) {
                    ?>
                    <p><h3><?= AmosDocumenti::t('amosdocumenti', 'Nessun allegato presente') ?></h3></p>
                    <?php
                } else {
                    ?>
                    <ul>
                        <?php
                        foreach ($allegati->all() as $Allegati) {
                            ?>
                            <li>
                                <a href="/documenti/documenti/download?idfile=<?= $Allegati['filemanager_mediafile_id'] ?>">
                                    <h3 title="Download file">
                                        <i><?= $Allegati['titolo'] ?></i>
                                    </h3>
                                </a>
                                <i><?= $Allegati['descrizione'] ?></i>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                    <?php
                }
                ?>

            </div>
        </div>

        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>
