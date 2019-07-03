import { PartnerManagerModule } from './partner-manager.module';

describe('PartnerManagerModule', () => {
  let partnerManagerModule: PartnerManagerModule;

  beforeEach(() => {
    partnerManagerModule = new PartnerManagerModule();
  });

  it('should create an instance', () => {
    expect(partnerManagerModule).toBeTruthy();
  });
});
