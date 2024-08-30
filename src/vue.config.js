module.exports = {
    publicPath: process.env.NODE_ENV === 'production' ? '/' : '/',
    compilerOptions: {
      isCustomElement: (tag) => tag === 'ckeditor'
    }
  };
  