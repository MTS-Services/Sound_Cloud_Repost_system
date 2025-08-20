<?php

namespace App\Services\Admin\Faq;

use App\Models\Faq;



class FaqService
{
        const KEY_CAMPAIGN = 'campaign';
       const KEY_REPOST = 'repost';
       const KEY_DIRECT_REPOST = 'direct_repost';
    /**
     * Create a new class instance.
     */
    public function __construct() {}

    public function getFaqs($orderBy = 'sort_order', $order = 'asc')
    {
        return Faq::orderBy($orderBy, $order)->latest();
    }
    public function getFaq(string $encryptedId)
    {
        return Faq::findOrFail(decrypt($encryptedId));
    }
    public function createFaq(array $data): Faq
    {
        $data['created_by'] = admin()->id;
        $faq = Faq::create($data);
        return $faq;
    }


     public function updateFaq(array $data, Faq $faq): Faq
    {
        $data['updated_by'] = admin()->id;
        $faq->update($data);
        return $faq;
    }

    public  function keyLists()
    {
        return [
            self::KEY_CAMPAIGN => "Campaign",
            self::KEY_REPOST => "Repost",
            self::KEY_DIRECT_REPOST => "Direct Repost",
        ];
    }
    public function toggleStatusS(Faq $faq)
    {
        // logic to toggle the faq status
        $faq->status = $faq->status === 'active' ? 'inactive' : 'active';
        $faq->save();
    }
    public function toggleStatus(Faq $faq): void
    {
        $faq->update([
            'status' => !$faq->status,
            'updater_id' => admin()->id,
            'updater_type' => get_class(admin())
        ]);
    }
    public function deleteFaq(Faq $faq, $id): void
    {
        $faq->delete($id);
    }

   public function restore(Faq $faq, $id): void
    {
        $faq->restore($id);
   }

    public function permanentDelete($faq,$id): void
    {
      $faq->forceDelete($id);
      
    }
}
