import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { LicenseTableComponent } from './license-table.component';

describe('LicenseTableComponent', () => {
  let component: LicenseTableComponent;
  let fixture: ComponentFixture<LicenseTableComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ LicenseTableComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(LicenseTableComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
