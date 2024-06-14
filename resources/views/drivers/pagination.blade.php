@if ($paginator->hasPages())
     <div style="text-align: center;">
          <p class="text-sm text-gray-700 leading-5 mt-3">
          Showing
          <span class="font-medium">{{ $paginator->firstItem() }}</span>
          to
          <span class="font-medium">{{ $paginator->lastItem() }}</span>
          of
          <span class="font-medium">{{ $paginator->total() }}</span>
          results
          </p>
     </div>
@endif