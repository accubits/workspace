import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { OrgDetailPopComponent } from './org-detail-pop.component';

describe('OrgDetailPopComponent', () => {
  let component: OrgDetailPopComponent;
  let fixture: ComponentFixture<OrgDetailPopComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ OrgDetailPopComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(OrgDetailPopComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
