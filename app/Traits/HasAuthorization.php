<?php

namespace App\Traits;

trait HasAuthorization
{
    public function hasAccessLevel($level)
    {
        return $this->AccessLevelID === $level;
    }

    public function hasAccessLevelOrHigher($level)
    {
        return $this->AccessLevelID >= $level;
    }

    public function belongsToDepartment($departmentId)
    {
        return $this->DepartmentID === $departmentId;
    }

    public function belongsToCity($cityId)
    {
        return $this->CityID === $cityId;
    }

    public function belongsToSubCity($subCityId)
    {
        return $this->SubCityID === $subCityId;
    }

    public function canAccessDocument(Document $document)
    {
        // Check if user has required access level
        if (!$this->hasAccessLevelOrHigher($document->access_level_id)) {
            return false;
        }

        // If document is public, allow access
        if ($document->IsPublic) {
            return true;
        }

        // Check department restriction
        if ($document->department_id && $document->department_id !== $this->DepartmentID) {
            return false;
        }

        // Check city restriction
        if ($document->city_id && $document->city_id !== $this->CityID) {
            return false;
        }

        // Check subcity restriction
        if ($document->subcity_id && $document->subcity_id !== $this->SubCityID) {
            return false;
        }

        return true;
    }

    public function canManageUsers()
    {
        return $this->hasAccessLevelOrHigher(3); // Assuming 3 is admin level
    }

    public function canManageDepartments()
    {
        return $this->hasAccessLevelOrHigher(3);
    }

    public function canUploadDocuments()
    {
        return $this->hasAccessLevelOrHigher(2); // Assuming 2 is editor level
    }

    public function canEditDocument(Document $document)
    {
        if ($this->hasAccessLevelOrHigher(3)) {
            return true;
        }

        return $document->user_id === $this->id && $this->canUploadDocuments();
    }

    public function canDeleteDocument(Document $document)
    {
        return $this->hasAccessLevelOrHigher(3);
    }
}
