import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { OrgTableHeadComponent } from './org-table-head.component';

describe('OrgTableHeadComponent', () => {
  let component: OrgTableHeadComponent;
  let fixture: ComponentFixture<OrgTableHeadComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ OrgTableHeadComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(OrgTableHeadComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
