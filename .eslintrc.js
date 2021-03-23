module.exports = {
  env: {
    browser: true
  },
  extends: [
    'standard',
    'eslint:recommended'
  ],
  rules: {
    'no-console': process.env.NODE_ENV === 'production' ? 'error' : 'off',
    'no-debugger': process.env.NODE_ENV === 'production' ? 'error' : 'off'
  },
  globals: {
    pimcore: 'writable',
    Ext: 'readonly',
    Class: 'readonly',
    Routing: 'readonly',
    t: 'readonly'
  }
}
