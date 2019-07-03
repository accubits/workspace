import { TestBed, inject } from '@angular/core/testing';

import { PartnerUtilityService } from './partner-utility.service';

describe('PartnerUtilityService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [PartnerUtilityService]
    });
  });

  it('should be created', inject([PartnerUtilityService], (service: PartnerUtilityService) => {
    expect(service).toBeTruthy();
  }));
});
