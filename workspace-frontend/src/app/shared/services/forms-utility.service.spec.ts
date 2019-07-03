import { TestBed, inject } from '@angular/core/testing';

import { FormsUtilityService } from './forms-utility.service';

describe('FormsUtilityService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [FormsUtilityService]
    });
  });

  it('should be created', inject([FormsUtilityService], (service: FormsUtilityService) => {
    expect(service).toBeTruthy();
  }));
});
