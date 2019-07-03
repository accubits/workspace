import { FilesizeMBPipe } from './filesize-mb.pipe';

describe('FilesizeMBPipe', () => {
  it('create an instance', () => {
    const pipe = new FilesizeMBPipe();
    expect(pipe).toBeTruthy();
  });
});
