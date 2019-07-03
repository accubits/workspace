import { TestBed, inject } from '@angular/core/testing';

import { PartnerDataService } from './partner-data.service';

describe('PartnerDataService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [PartnerDataService]
    });
  });

  it('should be created', inject([PartnerDataService], (service: PartnerDataService) => {
    expect(service).toBeTruthy();
  }));
});
