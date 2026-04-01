<div class="btn-list flex-nowrap">
    @if($review->status === \App\Models\Review::STATUS_PENDING)
    <button class="btn btn-sm btn-ghost-success btn-approve-review"
            data-url="{{ route('admin.reviews.approve', $review) }}" title="{{ __('admin.reviews.approve') }}">
        <i class="ti ti-check icon"></i>
    </button>
    <button class="btn btn-sm btn-ghost-danger btn-reject-review"
            data-url="{{ route('admin.reviews.reject', $review) }}" title="{{ __('admin.reviews.reject') }}">
        <i class="ti ti-x icon"></i>
    </button>
    @elseif($review->status === \App\Models\Review::STATUS_APPROVED)
    <button class="btn btn-sm btn-ghost-danger btn-reject-review"
            data-url="{{ route('admin.reviews.reject', $review) }}" title="{{ __('admin.reviews.reject') }}">
        <i class="ti ti-x icon"></i>
    </button>
    @elseif($review->status === \App\Models\Review::STATUS_REJECTED)
    <button class="btn btn-sm btn-ghost-success btn-approve-review"
            data-url="{{ route('admin.reviews.approve', $review) }}" title="{{ __('admin.reviews.approve') }}">
        <i class="ti ti-check icon"></i>
    </button>
    @endif
    <button class="btn btn-sm btn-ghost-danger btn-delete-review"
            data-url="{{ route('admin.reviews.destroy', $review) }}" title="{{ __('common.delete') }}">
        <i class="ti ti-trash icon"></i>
    </button>
</div>
