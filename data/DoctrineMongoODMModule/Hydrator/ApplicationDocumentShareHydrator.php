<?php

namespace DoctrineMongoODMModule\Hydrator;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Doctrine\ODM\MongoDB\Hydrator\HydratorInterface;
use Doctrine\ODM\MongoDB\UnitOfWork;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ODM. DO NOT EDIT THIS FILE.
 */
class ApplicationDocumentShareHydrator implements HydratorInterface
{
    private $dm;
    private $unitOfWork;
    private $class;

    public function __construct(DocumentManager $dm, UnitOfWork $uow, ClassMetadata $class)
    {
        $this->dm = $dm;
        $this->unitOfWork = $uow;
        $this->class = $class;
    }

    public function hydrate($document, $data, array $hints = array())
    {
        $hydratedData = array();

        /** @Field(type="id") */
        if (isset($data['_id'])) {
            $value = $data['_id'];
            $return = $value instanceof \MongoId ? (string) $value : $value;
            $this->class->reflFields['id']->setValue($document, $return);
            $hydratedData['id'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['shareid'])) {
            $value = $data['shareid'];
            $return = (string) $value;
            $this->class->reflFields['shareid']->setValue($document, $return);
            $hydratedData['shareid'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['actionid'])) {
            $value = $data['actionid'];
            $return = (string) $value;
            $this->class->reflFields['actionid']->setValue($document, $return);
            $hydratedData['actionid'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['shareuserid'])) {
            $value = $data['shareuserid'];
            $return = (string) $value;
            $this->class->reflFields['shareuserid']->setValue($document, $return);
            $hydratedData['shareuserid'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['sharetype'])) {
            $value = $data['sharetype'];
            $return = (string) $value;
            $this->class->reflFields['sharetype']->setValue($document, $return);
            $hydratedData['sharetype'] = $return;
        }
        return $hydratedData;
    }
}