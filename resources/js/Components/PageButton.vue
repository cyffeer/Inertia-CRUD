<template>
    <div class="flex space-x-2">
      <svg width="100" height="50" xmlns="http://www.w3.org/2000/svg" style="font-family: Arial, sans-serif;" @click="handleClick">
        <g cursor="pointer">
          <rect x="10" y="10" width="80" height="30" fill="#ff69b4" rx="5" class="button-rect"/>
          <polygon v-if="type === 'previous'" points="30,25 40,15 40,35" fill="#fff" />
          <polygon v-if="type === 'next'" points="40,15 50,25 40,35" fill="#fff" />
        </g>
      </svg>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      type: {
        type: String,
        required: true
      },
      onClick: {
        type: Function,
        required: true
      }
    },
    methods: {
      handleClick() {
        this.onClick();
      }
    }
  }
  </script>
  
  <style scoped>
  .button-rect:hover {
    fill: #0056b3;
  }
  .flex {
    display: flex;
    justify-content: center;
    align-items: center;
  }
  .space-x-2 > :not([hidden]) ~ :not([hidden]) {
    margin-left: 0.5rem; 
  }
  </style>
