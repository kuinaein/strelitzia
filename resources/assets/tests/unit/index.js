import sinonTestFactory from 'sinon-test';
const sinonTest = sinonTestFactory(sinon);

import axios from 'axios';
import AxiosMockAdapter from 'axios-mock-adapter';

const meta = document.createElement('meta');
meta.name = 'csrf-token';
meta.content = 'csrf-token';
document.querySelector('head').appendChild(meta);

// eslint-disable-next-line no-undef
require('@/bootstrap');
// eslint-disable-next-line no-undef
require('@/app');

const INVALID_URL = 'https://localhost:25/';

describe('axios', () => {
  /**
   * @type {AxiosMockAdapter}
   */
  let axiosMock;

  before(() => {
    axiosMock = new AxiosMockAdapter(window.axios);
  });

  after(() => {
    axiosMock.restore();
  });

  it('エラー時にコンソールにログを出力すること', sinonTest(function () {
    const consoleSpy = this.spy(console, 'error');
    axiosMock.onGet().reply(500);
    return axios.get(INVALID_URL).catch(()=>{}).finally(() => {
      try {
        assert(1 <= consoleSpy.callCount);
      } finally {
        consoleSpy.restore();
      }
    });
  }));

  it('422エラーを入力チェックエラーとして整形表示すること', () => {
    axiosMock.onGet().reply(422, {errors: {
      '名前': ['入力してください'],
      'メールアドレス': ['入力してください'],
    }});
    return axios.get(INVALID_URL).catch(err => {
      assert(`
* 名前
  * 入力してください
* メールアドレス
  * 入力してください` === err.message);
    });
  });
});
