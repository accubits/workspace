import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { LicenseFilterComponent } from './license-filter.component';

describe('LicenseFilterComponent', () => {
  let component: LicenseFilterComponent;
  let fixture: ComponentFixture<LicenseFilterComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ LicenseFilterComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(LicenseFilterComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
