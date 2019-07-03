import { Check2Module } from './check2.module';

describe('Check2Module', () => {
  let check2Module: Check2Module;

  beforeEach(() => {
    check2Module = new Check2Module();
  });

  it('should create an instance', () => {
    expect(check2Module).toBeTruthy();
  });
});
