export default {
    presets: [
      [
        '@babel/preset-env',
        {
          targets: {
            node: '12', // You can adjust this to the Node.js version you want to support
          },
          useBuiltIns: 'entry', // Adds necessary polyfills based on your environment
          corejs: 3, // Ensure you're using CoreJS version 3 for polyfills
        },
      ],
      '@babel/preset-react',
    ],
    plugins: [],
  };