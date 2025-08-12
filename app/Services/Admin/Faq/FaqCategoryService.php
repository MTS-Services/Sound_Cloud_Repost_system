<?php

namespace App\Services\Admin\Faq;
    

use App\Models\FaqCategory;

class FaqCategoryService
{
       

    public function getFaqCategories($orderBy = 'sort_order', $order = 'asc')
{
    return FaqCategory::orderBy($orderBy, $order)->latest();
}
    public function getFaqCategory(string $encryptedId)
    {
        return FaqCategory::where('id', decrypt($encryptedId))->first();
    }
    public function createFaqCategory(array $data): FaqCategory
    {
        $data['created_by'] = admin()->id;
        $faqCategory = FaqCategory::create($data);
        return $faqCategory;
    }


    public function updateFaqCategory(array $data, FaqCategory $faqCategory): FaqCategory
    {
        $data['updated_by'] = admin()->id;
        $faqCategory->update($data);
        return $faqCategory;
    }

       public function toggleStatusS(FaqCategory $faqCategory)
    {
        // logic to toggle the faq status
        $faqCategory->status = $faqCategory->status === 'active' ? 'inactive' : 'active';
        $faqCategory->save();
    }
    public function toggleStatus(FaqCategory $faqCategory): void
    {
        $faqCategory->update([
            'status' => !$faqCategory->status,
            'updater_id' => admin()->id,
            'updater_type' => get_class(admin())
        ]);
    }
    public function deleteFaqCategory(FaqCategory $faqCategory, $id): void
    {
        $faqCategory->delete($id);
    }

   public function restore(FaqCategory $faqCategory, $id): void
    {
        $faqCategory->restore($id);
   }

    public function permanentDelete($faqCategory,$id): void
    {
      $faqCategory->forceDelete($id);
      
    }
}
