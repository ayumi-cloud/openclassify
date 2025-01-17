<?php namespace Visiosoft\MediaFieldType\Table;

use Anomaly\FileFieldType\FileFieldType;
use Visiosoft\MediaFieldType\MediaFieldType;
use Anomaly\FilesModule\File\FileModel;
use Anomaly\Streams\Platform\Ui\Table\TableBuilder;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class ValueTableBuilder
 *
 * @link          http://openclassify.com/
 * @author        OpenClassify, Inc. <support@openclassify.com>
 * @author        Visiosoft Inc <support@openclassify.com>
 */
class ValueTableBuilder extends TableBuilder
{

    /**
     * The uploaded IDs.
     *
     * @var array
     */
    protected $uploaded = [];

    /**
     * The field type.
     *
     * @var null|FileFieldType
     */
    protected $fieldType = null;

    /**
     * The table model.
     *
     * @var string
     */
    protected $model = FileModel::class;

    /**
     * The table columns.
     *
     * @var array
     */
    protected $columns = [
        'path' => '{entry.path}',
    ];

    /**
     * The table buttons.
     *
     * @var array
     */
    protected $buttons = [
        'window'   => [
            'text' => 'visiosoft.field_type.media::button.showOn',
            'icon' => 'fa fa-eye',
            'type' => 'success',
            'data-id' => 'entry.id',
            'attributes' => [
                'id' => 'setimage',
                'onclick' => 'setMain({entry.id})'
            ],
        ],
        'rotate'   => [
            'target'     => '_blank',
            'icon' => 'fas fa-redo',
            'type' => 'info',
            'text' => '',
            'attributes' => [
                'id' => 'setimage',
                'data-action' => 'rotate-image',
            ],
        ],
        'remove' => [
            'data-dismiss' => 'file',
            'data-file'    => 'entry.id',
            'text' => '',
            'attributes' => [
                'id' => 'setimage',
            ],
        ],
    ];

    /**
     * The table options.
     *
     * @var array
     */
    protected $options = [
        'limit'              => 9999,
        'show_headers'       => false,
        'sortable_headers'   => false,
        'table_view'         => 'visiosoft.field_type.media::table/table',
        'no_results_message' => 'visiosoft.field_type.media::message.no_files_selected',
    ];

    /**
     * The table assets.
     *
     * @var array
     */
    protected $assets = [
        'styles.css' => [
            'visiosoft.field_type.media::less/input.less',
        ],
    ];

    /**
     * Fired just before querying
     * for table entries.
     *
     * @param Builder $query
     */
    public function onQuerying(Builder $query)
    {
        $uploaded = $this->getUploaded();

        if ($fieldType = $this->getFieldType()) {

            /*
             * If we have the entry available then
             * we can determine saved sort order.
             */
            $entry = $fieldType->getEntry();
            $table = $fieldType->getPivotTableName();

            if ($entry->getId() && !$uploaded) {
                $query->join($table, $table . '.file_id', '=', 'files_files.id');
                $query->where($table . '.entry_id', $entry->getId());
                $query->orderBy($table . '.sort_order', 'ASC');
            } else {
                $query->whereIn('id', $uploaded ?: [0]);
            }
        } else {

            /*
             * If all we have is ID then just use that.
             * The JS / UI will be handling the sort
             * order at this time.
             */
            $query->whereIn('id', $uploaded ?: [0]);
        }
    }

    /**
     * Get the field type.
     *
     * @return MediaFieldType|null
     */
    public function getFieldType()
    {
        return $this->fieldType;
    }

    /**
     * Set the field type.
     *
     * @param  MediaFieldType $fieldType
     * @return $this
     */
    public function setFieldType(MediaFieldType $fieldType)
    {
        $this->fieldType = $fieldType;

        return $this;
    }

    /**
     * Get the uploaded.
     *
     * @return array
     */
    public function getUploaded()
    {
        return $this->uploaded;
    }

    /**
     * Set the uploaded.
     *
     * @param $uploaded
     * @return $this
     */
    public function setUploaded($uploaded)
    {
        $this->uploaded = $uploaded;

        return $this;
    }

    /**
     * Set the table entries.
     *
     * @param  \Illuminate\Support\Collection $entries
     * @return $this
     */
    public function setTableEntries(\Illuminate\Support\Collection $entries)
    {
        if (!$this->getFieldType()) {
            $entries = $entries->sort(
                function ($a, $b) {
                    return array_search($a->id, $this->getUploaded()) - array_search($b->id, $this->getUploaded());
                }
            );
        }

        return parent::setTableEntries($entries);
    }
}
