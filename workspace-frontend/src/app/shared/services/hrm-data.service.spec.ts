import { TestBed, inject } from '@angular/core/testing';

import { HrmDataService } from './hrm-data.service';

describe('HrmDataService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [HrmDataService]
    });
  });

  it('should be created', inject([HrmDataService], (service: HrmDataService) => {
    expect(service).toBeTruthy();
  }));
});
