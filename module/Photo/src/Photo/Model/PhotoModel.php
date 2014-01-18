<?php
/**
 * Created by JetBrains PhpStorm.
 * User: FUHU
 * Date: 12/25/13
 * Time: 2:18 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Photo\Model;

class PhotoModel
{
    public function checkExistsAlbum($userid, $albumType,$dm)
    {
        $albumID = 'ALB'.$userid.$albumType;
        $document = $dm->createQueryBuilder('Application\Document\Album')
            ->field('userid')->equals($userid)
            ->field('albumid')->equals($albumID)
            ->getQuery()
            ->getSingleResult();
        if(isset($document)){
            return $document->getAlbumid();
        }else{
            return null;
        }
    }

    public function getListImageAvatar($userid, $albumType, $dm)
    {
        $checkExistsALB = $this->checkExistsAlbum($userid, $albumType, $dm);
        if($checkExistsALB != null)
        {
            $document = $dm->createQueryBuilder('Application\Document\Image')
                ->field('albumid')->equals($checkExistsALB)
                ->getQuery()
                ->execute();
            if(isset($document)){
                $arr = array();
                foreach($document as $doc){
                    $imageID = $doc->getImageid();
                    $arr[$imageID] = array(
                        'imageID'   => $imageID,
                        'imagePath' => $imageID.'.'.$doc->getImagetype(),
                        'imageDescript' => $doc->getImagedescription(),
                    );
                }
                return $arr;
            }else{
                return null;
            }
        }
        else
            return null;
    }

    public function  getListVideo($userid, $dm)
    {
        $checkExistsALB = $this->checkExistsAlbum($userid, "VID", $dm);

        if($checkExistsALB != null)
        {
            $document = $dm->createQueryBuilder('Application\Document\Video')
                ->field('albumid')->equals($checkExistsALB)
                ->getQuery()
                ->execute();
            if(isset($document))
            {
                $arr = array();
                foreach($document as $doc)
                {
                    $videoID = $doc->getVideoid();
                    $arr[$videoID] = array(
                        'videoID'       => $videoID,
                        'videoPath'     => $videoID.'.'.$doc->getVideotype(),
                        'videoType'     => $doc->getVideotype(),
                        'videoDescript' => $doc->getVideodescription(),
                    );
                }
                return $arr;
            }
            else
                return null;
        }
        else
        {
            return null;
        }
    }


}