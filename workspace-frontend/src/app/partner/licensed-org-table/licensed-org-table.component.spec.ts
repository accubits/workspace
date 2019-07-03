import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { LicensedOrgTableComponent } from './licensed-org-table.component';

describe('LicensedOrgTableComponent', () => {
  let component: LicensedOrgTableComponent;
  let fixture: ComponentFixture<LicensedOrgTableComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ LicensedOrgTableComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(LicensedOrgTableComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
