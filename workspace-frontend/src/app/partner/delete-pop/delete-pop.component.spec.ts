import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DeletePopComponent } from './delete-pop.component';

describe('DeletePopComponent', () => {
  let component: DeletePopComponent;
  let fixture: ComponentFixture<DeletePopComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DeletePopComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DeletePopComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
