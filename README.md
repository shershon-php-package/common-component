## 1.常用的通用组件

常用的通用组件:
- 基于 psr 封装的http通信组件

## 2.安装

- 配置composer.json
```json
{
  "require-dev": {
    "shershon/common": "^1.0.0"
  },
  "config": {
    "secure-http": false
  },
  "repositories": [
    {
      "type": "git",
      "url": "https://github.com/shershon-php-package/common-component.git"
    }
  ]
}
```

- composer require --ignore-platform-reqs shershon/common
- rm -rf vendor/shershon/common/.git

## 3.更新包版本
- composer require --ignore-platform-reqs shershon/common:1.0.0(替换成指定的版本)
- rm -rf vendor/shershon/common/.git