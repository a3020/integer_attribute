<?php
namespace Concrete\Package\IntegerAttribute\Attribute\Integer;

use Core;
use Database;

class Controller extends \Concrete\Core\Attribute\Controller
{
    protected $searchIndexFieldDefinition = [
        'type' => 'integer',
        'options' => ['precision' => 14, 'scale' => 0, 'default' => 0, 'notnull' => false],
    ];

    /**
     * @return int
     */
    public function getValue()
    {
        $db = Database::connection();

        return (int) $db->fetchColumn("SELECT value FROM atInteger where avID = ?", [$this->getAttributeValueID()]);
    }

    /**
     * @return int
     */
    public function getDisplayValue()
    {
        return $this->getValue();
    }

    /**
     * @param $list
     *
     * @return mixed
     */
    public function searchForm($list)
    {
        $numFrom = (int) $this->request('from');
        $numTo = (int) $this->request('to');
        if ($numFrom) {
            $list->filterByAttribute($this->attributeKey->getAttributeKeyHandle(), $numFrom, '>=');
        }

        if ($numTo) {
            $list->filterByAttribute($this->attributeKey->getAttributeKeyHandle(), $numTo, '<=');
        }

        return $list;
    }

    public function search()
    {
        $f = Core::make('helper/form');
        $html = $f->number($this->field('from'), $this->request('from'), ['step' => 1]);
        $html .= ' ' . t('to') . ' ';
        $html .= $f->number($this->field('to'), $this->request('to'), ['step' => 1]);
        echo $html;
    }

    public function form()
    {
        if (is_object($this->attributeValue)) {
            $value = $this->getAttributeValue()->getValue();
        }

        echo Core::make('helper/form')->number($this->field('value'), $value, [
            'style' => 'width:80px',
            'step' => 1,
        ]);
    }

    public function validateForm($p)
    {
        return $p['value'] != false;
    }

    public function validateValue()
    {
        $val = $this->getValue();

        return $val !== null && $val !== false;
    }

    public function saveValue($value)
    {
        $db = Database::connection();
        $value = ($value == false || $value == '0') ? 0 : $value;
        $db->Replace('atInteger', ['avID' => $this->getAttributeValueID(), 'value' => $value], 'avID', true);
    }

    public function deleteKey()
    {
        $db = Database::connection();
        $arr = $this->attributeKey->getAttributeValueIDList();
        foreach ($arr as $id) {
            $db->executeQuery("DELETE FROM atInteger WHERE avID = ?", [$id]);
        }
    }

    public function saveForm($data)
    {
        $this->saveValue($data['value']);
    }

    public function deleteValue()
    {
        if (!$this->hasAttributeValueID()) {
            return;
        }

        $db = Database::connection();
        $db->executeQuery("DELETE FROM atInteger WHERE avID = ?", [$this->getAttributeValueID()]);
    }
}
