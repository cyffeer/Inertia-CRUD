<template>
  <div class="py-3 border-gray-200 dark:border-gray-900">
    <nav class="flex justify-center">
      <ul class="flex list-none p-0">
        <li class="mx-1">
          <PageButton type="previous" :onClick="setPrevPage" />
        </li>

        <li v-for="page in pages" :key="page" :class="pageClasses(page)"
            class="mx-1" @click.prevent="setPage(page)">
          <a href="#" class="block px-3 py-2 border rounded text-gray-700 hover:bg-gray-200"
             :class="{ 'bg-pink-500 text-white': page === currentPage }">
            {{ page }}
          </a>
        </li>

        <li class="mx-1">
          <PageButton type="next" :onClick="setNextPage" />
        </li>
      </ul>
    </nav>
  </div>
</template>

<script>
import PageButton from '@/Components/PageButton.vue';

export default {
  components: {
    PageButton
  },
  props: {
    page: {
      type: Number,
      required: true
    },
    totalPages: {
      type: Number,
      required: true
    },
    onPageChange: {
      type: Function,
      required: true
    }
  },
  computed: {
    pages() {
      const pages = [];
      for (let i = 1; i <= this.totalPages; i++) {
        pages.push(i);
      }
      return pages;
    },
    currentPage() {
      return this.page;
    }
  },
  methods: {
    setPage(page) {
      this.onPageChange(page);
    },
    setNextPage() {
      if (this.page < this.totalPages) {
        this.onPageChange(this.page + 1);
      }
    },
    setPrevPage() {
      if (this.page > 1) {
        this.onPageChange(this.page - 1);
      }
    },
    pageClasses(page) {
      return {
        'font-bold': page === this.page
      };
    }
  }
}
</script>

<style scoped>
.paginate {
  display: flex;
  list-style: none;
  padding: 0;
}

.paginate-page-item {
  margin: 0 5px;
}

.paginate-page-item a {
  text-decoration: none;
  color: inherit;
}

.paginate-page-item.active a {
  font-weight: bold;
}
</style>