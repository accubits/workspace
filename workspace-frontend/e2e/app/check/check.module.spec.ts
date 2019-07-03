import { CheckModule } from './check.module';

describe('CheckModule', () => {
  let checkModule: CheckModule;

  beforeEach(() => {
    checkModule = new CheckModule();
  });

  it('should create an instance', () => {
    expect(checkModule).toBeTruthy();
  });
});
