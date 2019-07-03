import { HrmModule } from './hrm.module';

describe('HrmModule', () => {
  let hrmModule: HrmModule;

  beforeEach(() => {
    hrmModule = new HrmModule();
  });

  it('should create an instance', () => {
    expect(hrmModule).toBeTruthy();
  });
});
