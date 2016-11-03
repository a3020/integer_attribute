<?php

namespace Concrete\Package\IntegerAttribute;

use Concrete\Core\Package\Package;
use Concrete\Core\Attribute\Key\Category as AttributeKeyCategory;
use Concrete\Core\Attribute\Type as AttributeType;

class Controller extends Package
{
    protected $pkgHandle = 'integer_attribute';
    protected $appVersionRequired = '5.7.5';
    protected $pkgVersion = '0.9.0';

    public function getPackageName()
    {
        return t('Integer Attribute');
    }

    public function getPackageDescription()
    {
        return t('Attribute that only allows whole numbers');
    }

    public function install()
    {
        $pkg = parent::install();

        $at = AttributeType::add('integer', t('Integer'), $pkg);

        $col = AttributeKeyCategory::getByHandle('collection');
        $col->associateAttributeKeyType($at);

        $col = AttributeKeyCategory::getByHandle('user');
        $col->associateAttributeKeyType($at);

        $col = AttributeKeyCategory::getByHandle('file');
        $col->associateAttributeKeyType($at);
    }
}
