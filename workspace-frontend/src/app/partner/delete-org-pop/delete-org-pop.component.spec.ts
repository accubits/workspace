import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DeleteOrgPopComponent } from './delete-org-pop.component';

describe('DeleteOrgPopComponent', () => {
  let component: DeleteOrgPopComponent;
  let fixture: ComponentFixture<DeleteOrgPopComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DeleteOrgPopComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DeleteOrgPopComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
