import { TestBed, inject } from '@angular/core/testing';

import { PartnerManagerDataService } from './partner-manager-data.service';

describe('PartnerManagerDataService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [PartnerManagerDataService]
    });
  });

  it('should be created', inject([PartnerManagerDataService], (service: PartnerManagerDataService) => {
    expect(service).toBeTruthy();
  }));
});
